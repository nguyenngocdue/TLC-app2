import { EditableList } from "../../editable-list/src/EditableList"
import { DropdownOnBlur, DropdownOnClickAndBlur, getEById } from "./EventHandler/functions"

export const EditableSelect = (params) => {

    const {
        id, name, width = 200,
        placeholder = "Select",
        dataSource = {},
        selected,
        allowFilter, allowClear, multiple,
    } = params

    // console.log(params)
    const dropdownId = `dropdown_${id}`
    const dropdownInputId = `dropdown_input_${id}`

    const floatingListId = `floatingList_${id}`
    const listParams = {
        id: floatingListId,
        width,
        dataSource,
        allowFilter, allowClear,
        float: true,
        dropdownInputId,
        // selected,
        onClick: (e) => {
            getEById(dropdownInputId).val(e.target.id)
            DropdownOnBlur({ floatingListId })

            console.log(`Selected 1111 ${e.target.id}`)
        }
    }

    const floatingList = EditableList(listParams)
    const clearStr = `<button tabindex="-1" type="button" class="hover:bg-red-500 px-2 rounded-full flex items-center"><i class="fa-solid fa-delete-left"></i></button>`
    const arrowDown = `<div class="px-2 flex items-center"><i class="fa-solid fa-chevron-down"></i></div>`

    const valueFieldSingle = `<input type="hidden1" name="${name}" id="${dropdownInputId}" value="${selected}"/>`
    const valueFieldMultiple = `Filed For Multi Values`
    const valueField = multiple ? valueFieldMultiple : valueFieldSingle

    const dropdown = `<div id="${dropdownId}" class="border rounded inline-block" tabindex="0" style="width:${width}px;">
        ${valueField}
        <div class="flex">
            <span class="m-1 w-full">${placeholder}</span>
            ${allowClear ? clearStr : ''}
            ${arrowDown}
        </div>
        </div>
        ${floatingList}
    `

    $(document).ready(() => {
        getEById(dropdownId).click(() => DropdownOnClickAndBlur({ floatingListId }))
    })

    return dropdown
}