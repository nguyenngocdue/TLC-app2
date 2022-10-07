<div class="fixed right-0 top-8 z-30 items-center justify-center overflow-y-auto overflow-x-hidden outline-none focus:outline-none"
    id="modal-id">
    <div class="relative my-6 mx-auto h-full w-[450px]">
        <!--content-->
        <div class="relative flex w-full flex-col border-0 bg-white shadow-lg outline-none focus:outline-none">
            <form action="{{ route($type . '_renderprop.update', Auth::id()) }}" method="post" id="form-edit">
                @method('PUT')
                @csrf
                <!--header-->
                <div class="flex items-start justify-between rounded-t border-b border-solid border-slate-200 p-3">
                    <h3 class="p-1 text-3xl font-semibold">
                        Edit Role ID
                    </h3>
                    <button
                        class="float-right ml-auto border-0 bg-transparent p-1 text-3xl font-semibold leading-none text-black opacity-5 outline-none focus:outline-none"
                        onclick="toggleModal('modal-id')" type="button">
                        <span
                            class="block h-6 w-6 bg-transparent text-2xl text-black opacity-5 outline-none focus:outline-none">
                            ×
                        </span>
                    </button>
                </div>
                <!--body-->
                <div class="relative flex-auto p-6">
                    <div class="row">
                        @php
                            $settingDatas = Auth::user()->settings;
                            $dataRender = array_diff_key($data, $settingDatas);
                        @endphp
                        @foreach ($data as $key => $value)
                            @if (array_key_exists($key, $dataRender))
                                <div class="col-md-2 col-12">
                                    <label for="{{ $key }}">{{ ltrim($key, '_') }}</label>
                                    <input type="checkbox" class="checkbox-toggle" name="{{ $key }}" checked>
                                </div>
                            @else
                                <div class="col-md-2 col-12">
                                    <label for="{{ $key }}">{{ ltrim($key, '_') }}</label>
                                    <input type="checkbox" class="checkbox-toggle" name="{{ $key }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!--footer-->
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 p-2">
                    <button
                        class="background-transparent mr-1 mb-1 px-6 py-2 text-sm font-bold uppercase text-red-500 outline-none transition-all duration-150 ease-linear focus:outline-none"
                        type="button" onclick="toggleModal('modal-id')">
                        Close
                    </button>
                    <button
                        class="mr-1 mb-1 rounded bg-emerald-500 p-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-lg focus:outline-none active:bg-emerald-600"
                        type="submit" onclick="toggleModal('modal-id')">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
