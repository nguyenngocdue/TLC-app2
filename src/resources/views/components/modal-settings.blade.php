<form action="{{ route($type . '_viewall.update', Auth::id()) }}" method="post" id="form-edit">
    @method('PUT')
    @csrf
    <x-feedback.modal title="{{$title}}" type="{{$type}}">
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
            $dataSetting = array_filter($dataSetting, function ($value) {
            $check = $value['hidden_view_all'] ?? null;
            return $check !== 'true';
            });
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
                    <label><input type="checkbox" class="checkbox-toggle" name="{{ $key }}">
                        {{ Str::title(Str::ucfirst(Str::replace('_', ' ', ltrim($key, '_')))) }}</label>
                </div>
                @else
                <div class="flex flex-col">
                    <label><input type="checkbox" class="checkbox-toggle" name="{{ $key }}" checked>
                        {{ Str::title(Str::ucfirst(Str::replace('_', ' ', ltrim($key, '_')))) }}
                    </label>
                </div>
                @endif
                @endforeach
            </div>
            @endif

        </div>
    </x-feedback.modal>
</form>