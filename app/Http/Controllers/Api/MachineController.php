<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Machine\StoreMachineRequest;
use App\Http\Requests\Machine\UpdateMachineRequest;
use App\Http\Resources\MachineCollection;
use App\Http\Resources\MachineResource;
use App\Http\Resources\MaintenanceRecordResource;
use App\Http\Resources\MovementHistoryResource;
use App\Models\Category;
use App\Models\Department;
use App\Models\Location;
use App\Models\Machine;
use App\Models\MachineType;
use App\Services\MachineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MachineController extends Controller
{
    public function __construct(
        private MachineService $machineService
    ) {}

    /**
     * Display a listing of machines.
     */
    public function index(Request $request): MachineCollection
    {
        $query = Machine::with(['category', 'machineType', 'department', 'location']);

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->category($request->category_id);
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $query->department($request->department_id);
        }

        // Apply location filter
        if ($request->filled('location_id')) {
            $query->location($request->location_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        
        $allowedSortFields = ['machine_code', 'machine_name', 'status', 'purchase_date', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min($request->get('per_page', 15), 100);

        return new MachineCollection($query->paginate($perPage));
    }

    /**
     * Store a newly created machine.
     */
    public function store(StoreMachineRequest $request): JsonResponse
    {
        $machine = $this->machineService->create(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Machine created successfully.',
            'data' => new MachineResource($machine->load(['category', 'department', 'location'])),
        ], 201);
    }

    /**
     * Display the specified machine.
     */
    public function show(Machine $machine): MachineResource
    {
        return new MachineResource(
            $machine->load(['category', 'machineType', 'department', 'location', 'createdBy', 'updatedBy'])
        );
    }

    /**
     * Update the specified machine.
     */
    public function update(UpdateMachineRequest $request, Machine $machine): JsonResponse
    {
        $machine = $this->machineService->update(
            $machine,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Machine updated successfully.',
            'data' => new MachineResource($machine->load(['category', 'department', 'location'])),
        ]);
    }

    /**
     * Remove the specified machine.
     */
    public function destroy(Request $request, Machine $machine): JsonResponse
    {
        // Only admin can delete
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Only administrators can delete machines.',
            ], 403);
        }

        $machine->delete();

        return response()->json([
            'message' => 'Machine deleted successfully.',
        ]);
    }

    /**
     * Get maintenance records for a machine.
     */
    public function maintenanceRecords(Request $request, Machine $machine)
    {
        $perPage = min($request->get('per_page', 10), 50);

        $records = $machine->maintenanceRecords()
            ->with('createdBy')
            ->paginate($perPage);

        return MaintenanceRecordResource::collection($records);
    }

    /**
     * Get movement history for a machine.
     */
    public function movementHistories(Request $request, Machine $machine)
    {
        $perPage = min($request->get('per_page', 10), 50);

        $histories = $machine->movementHistories()
            ->with(['fromDepartment', 'toDepartment', 'fromLocation', 'toLocation', 'createdBy'])
            ->paginate($perPage);

        return MovementHistoryResource::collection($histories);
    }

    /**
     * Get dashboard statistics.
     */
    public function statistics(): JsonResponse
    {
        return response()->json([
            'data' => $this->machineService->getStatistics(),
        ]);
    }

    /**
     * Downloadable CSV/Excel template for machine import.
     */
    public function template(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $format = $request->query('format', 'csv');
        $header = [
            'CATEGORY', 'MACHINE TYPE', 'MACHINE CODE', 'PLATE NO.',
            'DETAIL DESCRIPTION', 'MODEL', 'CHASSIS NO./SERIAL NO.',
            'ENGINE TYPE / MODEL', 'ENGINE S.NO',
            'CURRENT STATUS', 'LOCATION'
        ];
        $example = [
            'Midlight Duty Vehicles', 'Double Cab Pick Up', 'EEC-10-05-001', 'HP-02-0181',
            'DOUBLE CABIN PICKUP HILUX 4X4 MT GD', 'HILUX', 'DLH1264XLF123456',
            '2KD-FTV', '1234567',
            'Working', 'ADAS'
        ];

        if ($format === 'excel' || $format === 'xlsx') {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Apply bold font to header
            $sheet->fromArray([$header, $example]);
            $sheet->getStyle('A1:K1')->getFont()->setBold(true);
            
            // Auto-size columns
            foreach (range('A', 'K') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $callback = function () use ($writer) {
                $writer->save('php://output');
            };

            return response()->streamDownload($callback, 'machines_import_template.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        // Default to CSV
        $filename = 'machines_import_template.csv';
        $callback = function () use ($header, $example) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $header);
            fputcsv($output, $example);
            fclose($output);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Bulk import machines from a CSV file.
     *
     * Expected header columns (case-insensitive):
     * - machine_code
     * - machine_name
     * - category
     * - department
     * - location
     * - serial_number
     * - status
     * - purchase_date (YYYY-MM-DD)
     * - remarks
     */
    public function import(Request $request): JsonResponse
    {
        if (!$request->user()->canEdit()) {
            return response()->json([
                'message' => 'Unauthorized. Only Admin/IT can import machines.',
            ], 403);
        }

        // Increase limits for large/complex Excel files
        set_time_limit(300);
        ini_set('memory_limit', '2048M');

        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->file('file');
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, false);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to read uploaded file: ' . $e->getMessage()], 422);
        }

        if (empty($rows) || !isset($rows[0])) {
            return response()->json(['message' => 'File appears to be empty.'], 422);
        }

        $header = array_values($rows[0]);
        if (!$header || count($header) < 2) {
            return response()->json(['message' => 'Unable to parse file header.'], 422);
        }
        
        // Remove header row so we only iterate data
        array_shift($rows);

        // Normalise header
        $normalizedHeader = array_map(function ($h) {
            $h = trim($h);
            $h = preg_replace('/^\xEF\xBB\xBF/', '', $h) ?? $h;
            return strtolower(Str::slug($h, '_'));
        }, $header);

        $expectedMap = [
            'machine_code'         => ['machine_code', 'code', 'asset_code', 'asset_no', 'eec_number'],
            'machine_name'         => ['machine_name', 'detail_description', 'description', 'name', 'asset_name'],
            'category'             => ['category', 'eec', 'category_name', 'group_name'],
            'machine_type_name'    => ['machine_type', 'machine_type_name', 'type_name'],
            'machine_group'        => ['machine_group', 'group'],
            'sub_category'         => ['sub_category', 'classification_code', 'category_code', 'machine_type_code'],
            'model'                => ['model', 'type'],
            'serial_number'        => ['serial_number', 'chassis_no_serial_no', 'chassis_noserial_no', 'chassis_no', 'serial_no', 'chassis', 'serial'],
            'engine_type'          => ['engine_type', 'engine_type_model', 'engine_model', 'engine_type_model', 'enginetype', 'engine'],
            'engine_serial_number' => ['engine_serial_number', 'engine_s_no', 'engine_sno', 'engine_serial_no', 'engine_serial', 'engine_no', 'enginesno', 'engineserialnumber'],
            'manufacturing_year'   => ['manufacturing_year', 'year', 'mfg_year'],
            'plate_number'         => ['plate_number', 'plate_no', 'plate'],
            'location'             => ['location', 'site'],
            'department'           => ['department', 'dept', 'cluster'],
            'status'               => ['status', 'current_status', 'condition'],
            'remarks'              => ['remarks', 'remark', 'notes', 'note'],
            'power'                => ['power', 'kw_hp', 'horsepower'],
            'weight'               => ['weight', 'kg'],
        ];

        $columnIndexes = [];
        foreach ($normalizedHeader as $idx => $normName) {
            if ($normName === '') continue;
            foreach ($expectedMap as $key => $aliases) {
                if (in_array($normName, $aliases)) {
                    $columnIndexes[$key] = $idx;
                    break;
                }
            }
        }

        // Fuzzy match critical columns
        foreach (['machine_code', 'machine_name', 'serial_number', 'engine_type', 'engine_serial_number'] as $critical) {
            if (!isset($columnIndexes[$critical])) {
                foreach ($header as $idx => $original) {
                    $fuzzy = strtolower(preg_replace('/[^a-z]/', '', $original));
                    foreach ($expectedMap[$critical] as $alias) {
                        $aliasFuzzy = strtolower(preg_replace('/[^a-z]/', '', $alias));
                        if ($fuzzy === $aliasFuzzy || str_contains($fuzzy, $aliasFuzzy)) {
                            $columnIndexes[$critical] = $idx;
                            break 2;
                        }
                    }
                }
            }
        }

        $requiredColumns = ['machine_code', 'category', 'machine_type_name'];
        foreach ($requiredColumns as $requiredColumn) {
            if (!isset($columnIndexes[$requiredColumn])) {
                return response()->json([
                    'message' => "Missing required column: {$requiredColumn}.",
                    'columns' => $normalizedHeader,
                ], 422);
            }
        }

        // -------------------------------------------------------
        // PASS 1: Read all rows + collect unique lookup values
        // -------------------------------------------------------
        set_time_limit(300);

        $statusMap = [
            'working'          => 'working', 'operable' => 'working', 'ok' => 'working', 'operational' => 'working',
            'faulty'           => 'faulty',  'broken'   => 'faulty',  'down' => 'faulty',
            'disposed'         => 'disposed','scrap'    => 'disposed',
            'under_maintenance'=> 'under_maintenance', 'maintenance' => 'under_maintenance',
        ];

        $allRows            = [];
        $neededCategories   = [];
        $neededDepts        = [];
        $neededLocations    = [];
        $neededMachineTypes = [];
        $skippedCount       = 0;
        $skippedDetails     = [];

        // Keep track of the row number (1-indexed, starting at 2 because row 1 is header)
        $currentRowNum = 1;

        while (count($rows) > 0) {
            $row = array_shift($rows);
            $currentRowNum++;
            
            if (count(array_filter($row, fn($v) => $v !== null && $v !== '')) === 0) continue;

            $rowData = [];
            foreach ($columnIndexes as $colName => $index) {
                $rowData[$colName] = isset($row[$index]) ? trim($row[$index]) : null;
            }

            $code = $rowData['machine_code'] ?? null;
            if (!$code) {
                $skippedCount++;
                $skippedDetails[] = [
                    'row_number' => $currentRowNum,
                    'reason'     => 'Missing Machine Code',
                    'data'       => $rowData
                ];
                continue;
            }
            if (empty($rowData['category'] ?? null)) {
                $skippedCount++;
                $skippedDetails[] = [
                    'row_number' => $currentRowNum,
                    'reason'     => 'Missing Category',
                    'data'       => $rowData
                ];
                continue;
            }
            if (empty($rowData['machine_type_name'] ?? null)) {
                $skippedCount++;
                $skippedDetails[] = [
                    'row_number' => $currentRowNum,
                    'reason'     => 'Missing Machine Type',
                    'data'       => $rowData
                ];
                continue;
            }

            $eecPrefixRaw       = $rowData['category'] ?? null;
            $categoryNameRaw    = $rowData['machine_group'] ?? ($rowData['category'] ?? null);
            $machineTypeNameRaw = $rowData['machine_type_name'] ?? null;
            $subCategoryCodeRaw = $rowData['sub_category'] ?? null;
            $fullCategoryName   = null;
            $categoryCode       = '';
            $eecPrefix          = '';

            if (!empty($categoryNameRaw)) {
                if (!empty($eecPrefixRaw)) {
                    $eecPrefix = strtoupper(str_replace(' ', '', $eecPrefixRaw));
                } elseif (preg_match('/^(EEC\s?-\s?\d+)/i', $code, $m)) {
                    $eecPrefix = strtoupper(str_replace(' ', '', $m[1]));
                }
                if (str_contains((string) $categoryNameRaw, '|')) {
                    $fullCategoryName = trim($categoryNameRaw);
                } else {
                    $fullCategoryName = !empty($eecPrefix) ? "{$eecPrefix} | {$categoryNameRaw}" : $categoryNameRaw;
                }
                $neededCategories[$fullCategoryName] = true;

                if (!empty($subCategoryCodeRaw)) {
                    $categoryCode = strtoupper(str_replace(' ', '', $subCategoryCodeRaw));
                } elseif (preg_match('/^(EEC\s?-\s?\d+-\d+)/i', $code, $m)) {
                    $categoryCode = strtoupper(str_replace(' ', '', $m[1]));
                } elseif (preg_match('/^([^-]+-[^-]+)/', $code, $m)) {
                    $categoryCode = $m[1];
                }

                if (!empty($categoryCode)) {
                    $mtKey = "{$fullCategoryName}|||{$categoryCode}";
                    $neededMachineTypes[$mtKey] = [
                        'categoryName' => $fullCategoryName,
                        'categoryCode' => $categoryCode,
                        'description'  => $machineTypeNameRaw ?: ($rowData['machine_name'] ?? '(Imported)'),
                        'eec_number'   => strtoupper(str_replace(' ', '', $code)),
                    ];
                }
            }

            if (!empty($rowData['department'] ?? null)) $neededDepts[$rowData['department']] = true;
            if (!empty($rowData['location'] ?? null))   $neededLocations[$rowData['location']] = true;

            $statusRaw = strtolower((string)($rowData['status'] ?? ''));
            $allRows[] = [
                'rowData'          => $rowData,
                'code'             => $code,
                'fullCategoryName' => $fullCategoryName,
                'categoryCode'     => $categoryCode,
                'machineTypeName'  => $machineTypeNameRaw,
                'status'           => $statusMap[$statusRaw] ?? 'working',
            ];
        }

        if (empty($allRows)) {
            return response()->json(['message' => 'No valid rows found in CSV.'], 422);
        }

        // -------------------------------------------------------
        // PASS 2: Bulk-insert missing lookup records
        // -------------------------------------------------------
        $now    = now();
        $userId = $request->user()->id;

        DB::beginTransaction();
        try {
            // --- Categories ---
            $categoryMap = [];
            if (!empty($neededCategories)) {
                $existing = \App\Models\Category::whereIn('name', array_keys($neededCategories))->pluck('id', 'name')->toArray();
                $missing  = array_keys(array_diff_key($neededCategories, $existing));
                if (!empty($missing)) {
                    DB::table('categories')->insert(array_map(fn($n) => ['name' => $n, 'created_at' => $now, 'updated_at' => $now], $missing));
                }
                $categoryMap = \App\Models\Category::whereIn('name', array_keys($neededCategories))->pluck('id', 'name')->toArray();
            }
            $allCategories = Category::select('id', 'name')->get();

            // --- Departments ---
            $departmentMap = [];
            if (!empty($neededDepts)) {
                $existing = \App\Models\Department::whereIn('name', array_keys($neededDepts))->pluck('id', 'name')->toArray();
                $missing  = array_keys(array_diff_key($neededDepts, $existing));
                if (!empty($missing)) {
                    DB::table('departments')->insert(array_map(fn($n) => ['name' => $n, 'created_at' => $now, 'updated_at' => $now], $missing));
                }
                $departmentMap = \App\Models\Department::whereIn('name', array_keys($neededDepts))->pluck('id', 'name')->toArray();
            }

            // --- Locations ---
            $locationMap = [];
            if (!empty($neededLocations)) {
                $existing = \App\Models\Location::whereIn('name', array_keys($neededLocations))->pluck('id', 'name')->toArray();
                $missing  = array_keys(array_diff_key($neededLocations, $existing));
                if (!empty($missing)) {
                    DB::table('locations')->insert(array_map(fn($n) => ['name' => $n, 'created_at' => $now, 'updated_at' => $now], $missing));
                }
                $locationMap = \App\Models\Location::whereIn('name', array_keys($neededLocations))->pluck('id', 'name')->toArray();
            }

            // --- Machine Types (needs category IDs) ---
            $machineTypeMap = [];
            $machineTypeByDescription = [];
            if (!empty($neededMachineTypes)) {
                $existingMTs = \App\Models\MachineType::select('id', 'category_id', 'category_code')->get();
                foreach ($existingMTs as $mt) {
                    $machineTypeMap["{$mt->category_id}|{$mt->category_code}"] = $mt->id;
                }
                $insertMTs = [];
                foreach ($neededMachineTypes as $mtData) {
                    $catId = $categoryMap[$mtData['categoryName']] ?? null;
                    if (!$catId) continue;
                    $mk = "{$catId}|{$mtData['categoryCode']}";
                    if (!isset($machineTypeMap[$mk])) {
                        $insertMTs[$mk] = [
                            'category_id'   => $catId,
                            'category_code' => $mtData['categoryCode'],
                            'description'   => $mtData['description'],
                            'eec_number'    => $mtData['eec_number'],
                            'created_at'    => $now,
                            'updated_at'    => $now,
                        ];
                    }
                }
                if (!empty($insertMTs)) {
                    DB::table('machine_types')->insert(array_values($insertMTs));
                    $freshMTs = \App\Models\MachineType::select('id', 'category_id', 'category_code')->get();
                    foreach ($freshMTs as $mt) {
                        $machineTypeMap["{$mt->category_id}|{$mt->category_code}"] = $mt->id;
                    }
                }
            }
            $allMachineTypes = MachineType::select('id', 'category_id', 'category_code', 'description')->get();
            foreach ($allMachineTypes as $mt) {
                $machineTypeMap["{$mt->category_id}|" . strtoupper(str_replace(' ', '', (string) $mt->category_code))] = $mt->id;
                $machineTypeByDescription[strtolower(trim((string) $mt->description)) . "|{$mt->category_id}"] = $mt->id;
            }

            // -------------------------------------------------------
            // PASS 3: Build machine payload & batch upsert (500/chunk)
            // -------------------------------------------------------
            $imported       = 0;
            $incomplete     = 0;
            $incompleteRows = [];
            $machineRows    = [];

            foreach ($allRows as $entry) {
                $rowData          = $entry['rowData'];
                $code             = $entry['code'];
                $fullCategoryName = $entry['fullCategoryName'];
                $categoryCode     = $entry['categoryCode'];
                $machineTypeName  = $entry['machineTypeName'];
                $status           = $entry['status'];

                $name = $rowData['machine_name'] ?? null;
                if (empty($name)) {
                    $name = '(Incomplete — ' . $code . ')';
                    $incomplete++;
                    $incompleteRows[] = $code;
                }

                $categoryId = $fullCategoryName ? ($categoryMap[$fullCategoryName] ?? null) : null;
                if (!$categoryId && $fullCategoryName) {
                    $categoryNeedle = strtolower(trim($fullCategoryName));
                    foreach ($allCategories as $category) {
                        $candidate = strtolower(trim($category->name));
                        $parts = array_map('trim', explode('|', $candidate));
                        $categoryLabel = strtolower($parts[1] ?? $candidate);
                        if ($candidate === $categoryNeedle || $categoryLabel === $categoryNeedle) {
                            $categoryId = $category->id;
                            break;
                        }
                    }
                }
                $machineTypeId = null;
                if ($categoryId && !empty($categoryCode)) {
                    $normalizedTypeCode = strtoupper(str_replace(' ', '', $categoryCode));
                    $machineTypeId = $machineTypeMap["{$categoryId}|{$normalizedTypeCode}"] ?? null;
                }
                if ($categoryId && !$machineTypeId && !empty($machineTypeName)) {
                    $machineTypeId = $machineTypeByDescription[strtolower(trim($machineTypeName)) . "|{$categoryId}"] ?? null;
                }
                $departmentId = !empty($rowData['department'] ?? null) ? ($departmentMap[$rowData['department']] ?? null) : null;
                $locationId   = !empty($rowData['location'] ?? null)   ? ($locationMap[$rowData['location']]   ?? null) : null;

                $machineRows[] = [
                    'machine_code'         => $code,
                    'machine_name'         => $name,
                    'category_id'          => $categoryId,
                    'machine_type_id'      => $machineTypeId,
                    'machine_type'         => $categoryCode ?: null,
                    'machine_group'        => $rowData['machine_group'] ?? null,
                    'department_id'        => $departmentId,
                    'location_id'          => $locationId,
                    'serial_number'        => $rowData['serial_number'] ?? null,
                    'plate_number'         => $rowData['plate_number'] ?? null,
                    'model'                => $rowData['model'] ?? null,
                    'engine_type'          => $rowData['engine_type'] ?? null,
                    'engine_serial_number' => $rowData['engine_serial_number'] ?? null,
                    'manufacturing_year'   => $rowData['manufacturing_year'] ?? null,
                    'status'               => $status,
                    'remarks'              => $rowData['remarks'] ?? null,
                    'created_by'           => $userId,
                    'updated_by'           => $userId,
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ];
                $imported++;
            }

            // Upsert in chunks of 500 rows at a time
            $updateColumns = [
                'machine_name', 'category_id', 'machine_type_id', 'machine_type',
                'machine_group', 'department_id', 'location_id', 'serial_number',
                'plate_number', 'model', 'engine_type', 'engine_serial_number',
                'manufacturing_year', 'status', 'remarks', 'updated_by', 'updated_at',
            ];
            foreach (array_chunk($machineRows, 500) as $chunk) {
                DB::table('machines')->upsert($chunk, ['machine_code'], $updateColumns);
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Import failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Import completed.',
            'data'    => [
                'imported'         => $imported,
                'skipped'          => $skippedCount,
                'incomplete'       => $incomplete,
                'incomplete_codes' => $incompleteRows,
                'skipped_rows'     => $skippedDetails,
                'matched_columns'  => array_keys($columnIndexes),
                'raw_header'       => $header,
                'errors'           => [],
            ],
        ]);
    }


    /**
     * Bulk delete machines.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Only administrators can perform bulk deletion.',
            ], 403);
        }

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:machines,id'],
        ]);

        $ids = $request->input('ids');
        $count = Machine::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => "Successfully deleted {$count} machine(s).",
            'data' => [
                'count' => $count,
            ],
        ]);
    }

    /**
     * Delete all machines.
     */
    public function deleteAll(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Only administrators can wipe the inventory.',
            ], 403);
        }

        $count = Machine::count();
        Machine::query()->delete();

        return response()->json([
            'message' => "Successfully cleared inventory. {$count} machine(s) deleted.",
            'data' => [
                'count' => $count,
            ],
        ]);
    }
}
