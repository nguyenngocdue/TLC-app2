<div class="fixed right-0 top-8 z-20 hidden items-center justify-center outline-none focus:outline-none"
    id="modal-setting-id">
    <div class="relative my-6 mx-auto h-full w-[750px]">
        <!--content-->
        <div class="relative flex h-full w-full flex-col border-0 bg-white shadow-lg outline-none focus:outline-none">
            <form action="{{ route($type . '_render.update', Auth::id()) }}" method="post" id="form-edit">
                @method('PUT')
                @csrf
                <!--header-->
                <div class="flex h-14 items-start justify-between rounded-t border-b border-solid border-slate-200 p-3">
                    <h3 class="p-1 text-2xl font-semibold">
                        Settings
                    </h3>
                    <button
                        class="float-right ml-auto border-0 bg-transparent p-1 text-3xl font-semibold leading-none text-black opacity-5 outline-none focus:outline-none"
                        onclick="toggleModalSetting('modal-setting-id')" type="button">
                        <span
                            class="block h-6 w-6 bg-transparent text-2xl text-black opacity-5 outline-none focus:outline-none">
                            ×
                        </span>
                    </button>
                </div>
                <!--body-->
                <div class='h-[calc(100%-112px)] overflow-y-scroll px-5 py-2'>
                    <label class="py-5 text-lg font-semibold text-black">Columns</label>
                    @php
                        $pathSetting = storage_path() . "/json/entities/$type/props.json";
                    @endphp
                    @if (!file_exists($pathSetting))
                        <span class="ml-5 flex text-sm text-red-500">Setting Developer Zone Entity</span>
                    @else
                        @php
                            $dataSetting = json_decode(file_get_contents($pathSetting), true);
                            $settingDatas = Auth::user()->settings;
                            if (isset($settingDatas[$type]['columns'])) {
                                $dataRender = array_diff_key($dataSetting, $settingDatas[$type]['columns']);
                            } else {
                                $dataRender = [];
                            }
                        @endphp
                        <div class="grid grid-cols-4 gap-x-2">
                            <input type="hidden" name='_entity' value="{{ $type }}">
                            @foreach ($dataSetting as $key => $value)
                                @if (array_key_exists($key, $dataRender))
                                    <div class="flex flex-col">
                                        <label><input type="checkbox" class="checkbox-toggle"
                                                name="{{ $key }}">
                                            {{ Str::title(Str::ucfirst(Str::replace('_', ' ', ltrim($key, '_')))) }}</label>
                                    </div>
                                @else
                                    <div class="flex flex-col">
                                        <label><input type="checkbox" class="checkbox-toggle" name="{{ $key }}"
                                                checked>
                                            {{ Str::title(Str::ucfirst(Str::replace('_', ' ', ltrim($key, '_')))) }}
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                </div>

                <!--footer-->
                <div class="flex h-14 items-center justify-end rounded-b border-t border-solid border-slate-200 p-2">
                    <button
                        class="background-transparent mr-1 mb-1 px-6 py-2 text-sm font-bold uppercase text-red-500 outline-none transition-all duration-150 ease-linear focus:outline-none"
                        type="button" onclick="toggleModalSetting('modal-setting-id')">
                        Close
                    </button>
                    <button
                        class="mr-1 mb-1 rounded bg-emerald-500 p-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-lg focus:outline-none active:bg-emerald-600"
                        type="submit" onclick="toggleModalSetting('modal-setting-id')">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
