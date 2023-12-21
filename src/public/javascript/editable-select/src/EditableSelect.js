export const EditableSelect = (params) => {

    const { placeholder = "Select" } = params

    return `<div class="border rounded inline-block" tabindex="0">
        <div class="flex">
            <span class="m-1">${placeholder}</span>
            <div class="border-l-1 px-2 flex items-center">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
    </div>`
}