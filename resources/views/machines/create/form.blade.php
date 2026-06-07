<x-app-layout title="New Equipment">
    @php
        $catParts = explode('|', $category->name);
        $categoryCode = trim($catParts[0] ?? '');
        $categoryLabel = trim($catParts[1] ?? $category->name);
    @endphp

    <div class="min-h-screen py-10 px-6 animate-in slide-in-from-bottom duration-500">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <a href="{{ route('machines.create.type', $category) }}" class="w-14 h-14 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 hover:text-cyan-500 hover:border-cyan-500 transition-all shadow-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-600 dark:text-cyan-400">Fleet Expansion</span>
                            <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span>
                        </div>
                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">New Equipment</h1>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $machineType->description }}</p>
                        <p class="text-xs text-gray-500 font-medium">{{ $categoryLabel }}</p>
                    </div>
                    <div class="flex items-center bg-green-500 px-6 py-3 rounded-2xl text-white font-bold text-sm shadow-xl shadow-green-500/20">
                        EEC Validated
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('machines.store') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <input type="hidden" name="machine_type_id" value="{{ $machineType->id }}">
                <input type="hidden" name="machine_type" value="{{ $machineType->eec_number }}">
                <input type="hidden" name="status" value="working">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="card p-8 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/50">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">General Identity</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="label font-bold text-xs uppercase tracking-widest text-gray-400">Classification</label>
                                <div class="mt-2 p-4 rounded-xl bg-white dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 shadow-inner">
                                    <span class="text-[10px] font-black text-cyan-600 tracking-tighter">{{ $categoryCode }} — {{ $machineType->category_code }}</span>
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-1">{{ $machineType->description }}</p>
                                    <span class="text-[10px] font-medium text-gray-400">System Reference: {{ $machineType->eec_number }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="label font-bold text-xs uppercase tracking-widest text-gray-400">Machine Code *</label>
                                <input type="text" name="machine_code" value="{{ old('machine_code') }}" placeholder="EQ-001" required
                                       class="input mt-1 h-12 @error('machine_code') input-error @enderror">
                                @error('machine_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label font-bold text-xs uppercase tracking-widest text-gray-400">Internal Asset Name</label>
                                <input type="text" name="machine_name" value="{{ old('machine_name') }}" placeholder="Display name for dashboard" class="input mt-1 h-12">
                                @error('machine_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 card p-8 border-transparent shadow-2xl">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">Technical Specifications</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Model</label><input type="text" name="model" value="{{ old('model') }}" class="input h-12"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Chassis No. / Serial No.</label><input type="text" name="serial_number" value="{{ old('serial_number') }}" class="input h-12"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Machine Group</label><input type="text" name="machine_group" value="{{ old('machine_group') }}" class="input h-12"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Power (Kw/HP)</label><input type="text" name="power" value="{{ old('power') }}" class="input h-12"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Weight (KG)</label><input type="number" step="0.01" name="weight" value="{{ old('weight') }}" class="input h-12"></div>
                            </div>
                            <div class="space-y-6">
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Engine Type / Model</label><input type="text" name="engine_type" value="{{ old('engine_type') }}" class="input h-12"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Engine Serial No.</label><input type="text" name="engine_serial_number" value="{{ old('engine_serial_number') }}" class="input h-12"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Plate No.</label><input type="text" name="plate_number" value="{{ old('plate_number') }}" class="input h-12"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Year</label><input type="number" name="manufacturing_year" value="{{ old('manufacturing_year') }}" class="input h-12"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <div class="lg:col-span-8 card p-8 shadow-xl">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">Acquisition & Financials</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-6">
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Purchase Date</label><input type="date" name="purchase_date" value="{{ old('purchase_date') }}" class="input h-11"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Received Date</label><input type="date" name="received_date" value="{{ old('received_date') }}" class="input h-11"></div>
                            </div>
                            <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                <div class="col-span-2"><label class="label text-xs uppercase tracking-widest text-gray-400">Supplier</label><input type="text" name="supplier" value="{{ old('supplier') }}" class="input h-11"></div>
                                <div><label class="label text-xs uppercase tracking-widest text-gray-400">Manufacturer</label><input type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="input h-11"></div>
                                <div class="col-span-2"><label class="label text-cyan-600 dark:text-cyan-400 text-xs uppercase tracking-widest">Price Value</label><input type="number" step="0.01" name="price" value="{{ old('price') }}" class="input h-12 text-xl font-black text-cyan-700 dark:text-cyan-400"></div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 card p-8 bg-indigo-600 dark:bg-indigo-900 text-white shadow-xl">
                        <h3 class="text-xl font-bold mb-8">Deployment</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-200/60 mb-2">Location</label>
                                <select name="location_id" class="block w-full h-12 px-4 bg-white/10 border border-white/20 rounded-xl text-white">
                                    <option value="" class="text-gray-900">Select location</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" @selected(old('location_id') == $loc->id) class="text-gray-900">{{ $loc->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-200/60 mb-2">Department</label>
                                <select name="department_id" class="block w-full h-12 px-4 bg-white/10 border border-white/20 rounded-xl text-white">
                                    <option value="" class="text-gray-900">Select department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id) class="text-gray-900">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-8 shadow-xl border-dashed border-2 border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-gray-500 mb-6">Additional Fleet Intelligence</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div><label class="label text-xs uppercase tracking-widest text-gray-400">Detail Description</label><textarea name="description" rows="4" class="input mt-2 min-h-[6rem]" placeholder="Summarize machine capabilities...">{{ old('description') }}</textarea></div>
                        <div><label class="label text-xs uppercase tracking-widest text-gray-400">Operator Remarks</label><textarea name="remarks" rows="4" class="input mt-2 min-h-[6rem]" placeholder="Critical notes or safety precautions...">{{ old('remarks') }}</textarea></div>
                    </div>
                </div>

                <div class="sticky bottom-8 z-50 flex flex-col sm:flex-row gap-4 items-center justify-between p-4 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl border border-gray-200 dark:border-gray-700 shadow-2xl">
                    <a href="{{ route('machines.create.type', $category) }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gray-100 dark:bg-gray-800 font-black text-xs uppercase tracking-widest text-gray-500 hover:bg-gray-200 transition-all text-center">Back to Type Selection</a>
                    <button type="submit" class="w-full sm:w-auto px-12 py-4 bg-gradient-to-r from-cyan-600 to-blue-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-cyan-500/30 hover:scale-105 transition-all">Confirm & Register</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
