<form action="{{ route('updateUserSettings') }}" method="post">
    @method('PUT')
    @csrf
    <x-feedback.modal title="{{$title}}" type="{{$type}}">
        <div class='h-[calc(100%-112px)] overflow-y-scroll px-5 py-2'>
            <label class="py-5 text-lg font-semibold text-black">Columns</label>
            <div class="grid grid-cols-4 gap-x-2">
                <input type="hidden" name='_entity' value="{{ $type }}">
                <input type="hidden" name='action' value="updateGear">
                @forelse ($allColumns as $key => $value)
                <div class="flex flex-col">
                    <label>
                        <input type="checkbox" class="checkbox-toggle" name="{{ $key }}" @checked(array_key_exists($key, $selected))>
                        {{ Str::pretty(trim($key, "_")) }}
                        </input>
                    </label>
                </div>
                @empty
                There is no prop to be found
                @endforelse
            </div>
        </div>
        <div class="flex h-14 items-center justify-end rounded-b border-t border-solid border-slate-200 p-2">
            <!--button class="background-transparent mr-1 mb-1 px-6 py-2 text-sm font-bold uppercase text-red-500 outline-none transition-all duration-150 ease-linear focus:outline-none" type="button" onclick="toggleModalSetting('modal-setting-id')">
            Close
        </button-->
            <button class="mr-1 mb-1 rounded bg-emerald-500 p-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-lg focus:outline-none active:bg-emerald-600" type="submit" onclick="toggleModalSetting('modal-setting-id')">
                Update
            </button>
        </div>
    </x-feedback.modal>
</form>