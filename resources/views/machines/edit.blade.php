<x-app-layout title="Edit {{ $machine->machine_code }}">
    <div class="space-y-6" x-data="{
        origDept: '{{ $machine->department_id }}',
        origLoc: '{{ $machine->location_id }}',
        showMovement: false,
        checkMovement() {
            const dept = document.getElementById('department_id')?.value || '';
            const loc = document.getElementById('location_id')?.value || '';
            this.showMovement = dept !== this.origDept || loc !== this.origLoc;
        }
    }">
        <div class="flex items-center gap-4">
            <a href="{{ route('machines.show', $machine) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Machine: {{ $machine->machine_code }}</h1>
        </div>

        <form method="POST" action="{{ route('machines.update', $machine) }}" class="card p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-2">
                    <label class="label">Machine Code *</label>
                    <input type="text" name="machine_code" value="{{ old('machine_code', $machine->machine_code) }}"
                           class="input @error('machine_code') input-error @enderror" required>
                    @error('machine_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="label">Machine Name *</label>
                    <input type="text" name="machine_name" value="{{ old('machine_name', $machine->machine_name) }}"
                           class="input @error('machine_name') input-error @enderror" required>
                    @error('machine_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Category</label>
                    <select name="category_id" class="input @error('category_id') input-error @enderror">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $machine->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Machine Type</label>
                    <select name="machine_type_id" class="input @error('machine_type_id') input-error @enderror">
                        <option value="">Select type</option>
                        @foreach($machineTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('machine_type_id', $machine->machine_type_id) == $type->id)>[{{ $type->category_code }}] {{ $type->description }}</option>
                        @endforeach
                    </select>
                    @error('machine_type_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Status *</label>
                    <select name="status" class="input @error('status') input-error @enderror" required>
                        @foreach(['working' => 'Working', 'faulty' => 'Faulty', 'under_maintenance' => 'Under Maintenance', 'disposed' => 'Disposed'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', $machine->status) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Model</label>
                    <input type="text" name="model" value="{{ old('model', $machine->model) }}" class="input @error('model') input-error @enderror">
                    @error('model')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Department</label>
                    <select name="department_id" id="department_id" @change="checkMovement()" class="input @error('department_id') input-error @enderror">
                        <option value="">Select department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(old('department_id', $machine->department_id) == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Location</label>
                    <select name="location_id" id="location_id" @change="checkMovement()" class="input @error('location_id') input-error @enderror">
                        <option value="">Select location</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" @selected(old('location_id', $machine->location_id) == $loc->id)>{{ $loc->full_name }}</option>
                        @endforeach
                    </select>
                    @error('location_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $machine->serial_number) }}" class="input">
                </div>

                <div>
                    <label class="label">Machine Group</label>
                    <input type="text" name="machine_group" value="{{ old('machine_group', $machine->machine_group) }}" class="input">
                </div>

                <div>
                    <label class="label">Engine Type</label>
                    <input type="text" name="engine_type" value="{{ old('engine_type', $machine->engine_type) }}" class="input">
                </div>

                <div>
                    <label class="label">Engine Serial No.</label>
                    <input type="text" name="engine_serial_number" value="{{ old('engine_serial_number', $machine->engine_serial_number) }}" class="input">
                </div>

                <div>
                    <label class="label">Plate Number</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number', $machine->plate_number) }}" class="input">
                </div>

                <div>
                    <label class="label">Power (Kw/HP)</label>
                    <input type="text" name="power" value="{{ old('power', $machine->power) }}" class="input">
                </div>

                <div>
                    <label class="label">Weight (KG)</label>
                    <input type="number" step="0.01" name="weight" value="{{ old('weight', $machine->weight) }}" class="input">
                </div>

                <div>
                    <label class="label">Purchase Date</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', $machine->purchase_date?->format('Y-m-d')) }}" class="input">
                </div>

                <div>
                    <label class="label">Received Date</label>
                    <input type="date" name="received_date" value="{{ old('received_date', $machine->received_date?->format('Y-m-d')) }}" class="input">
                </div>

                <div>
                    <label class="label">Year</label>
                    <input type="number" name="manufacturing_year" value="{{ old('manufacturing_year', $machine->manufacturing_year) }}" class="input">
                </div>

                <div>
                    <label class="label">Manufacturer</label>
                    <input type="text" name="manufacturer" value="{{ old('manufacturer', $machine->manufacturer) }}" class="input">
                </div>

                <div>
                    <label class="label">Supplier</label>
                    <input type="text" name="supplier" value="{{ old('supplier', $machine->supplier) }}" class="input">
                </div>

                <div>
                    <label class="label">Price Value</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $machine->price) }}" class="input">
                </div>

                <div class="lg:col-span-4 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg" x-show="showMovement" x-cloak>
                    <label class="label text-yellow-800 dark:text-yellow-400">Movement Reason (optional)</label>
                    <input type="text" name="movement_reason" value="{{ old('movement_reason') }}" placeholder="Why is this machine being moved?" class="input">
                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-500">Department or location changed. This will be recorded in movement history.</p>
                </div>

                <div class="lg:col-span-4">
                    <label class="label">Detail Description</label>
                    <input type="text" name="description" value="{{ old('description', $machine->description) }}" class="input">
                </div>

                <div class="lg:col-span-4">
                    <label class="label">Remarks</label>
                    <textarea name="remarks" rows="3" class="input">{{ old('remarks', $machine->remarks) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('machines.show', $machine) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
