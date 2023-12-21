import { EditableList } from "../../editable-list/src/EditableList"

export const EditableSelect = (params) => {

    const {
        id, width = 200,
        placeholder = "Select",
        dataSource = {},
        allowFilter,
        allowClear,
    } = params

    console.log(params)

    const floatingListId = `floatingList_${id}`
    const listParams = {
        id: floatingListId,
        width,
        dataSource,
        allowFilter,
        allowClear,
        float: true,
    }

    const floatingList = EditableList(listParams)
    const clearStr = `<button type="button" class="hover:bg-red-500 px-2 rounded-full flex items-center"><i class="fa-solid fa-delete-left"></i></button>`
    const arrowDown = `<div class="px-2 flex items-center"><i class="fa-solid fa-chevron-down"></i></div>`
    const dropdownId = `dropdown_${id}`

    const dropdown = `<div id="${dropdownId}" class="border rounded inline-block" tabindex="0" style="width:${width}px;">
        <div class="flex">
            <span class="m-1 w-full">${placeholder}</span>
            ${allowClear ? clearStr : ''}
            ${arrowDown}
        </div>
        </div>
        ${floatingList}
    `

    $(document).ready(() => {
        $(`#${dropdownId}`).click(() => {
            console.log(`Clicked on ${dropdownId}`)
            $(`#${floatingListId}`).removeClass('hidden')
            $(`#${floatingListId}`).addClass('absolute')
        })
    })

    return dropdown
}