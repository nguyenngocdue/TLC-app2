import { helloList } from "../../editable-list/src/functions"
import { EditableSelect } from "./EditableSelect"

const Select1 = () => {
    const params = {
        id: 'Select1_a',
        name: 'Select1',
        placeholder: "Select country",
        readOnly: false,
        allowClear: false,
        allowFilter: false,
        multiple: false,
        itemTemplate: (obj) => obj,
        selected: 1,
        dataSource: helloList,
        width: 210,
    }
    const select = EditableSelect(params)
    return select
}
const Select2 = () => {
    const params = {
        id: 'Select2[a]',
        name: 'Select2',
        placeholder: "Select country",
        readOnly: false,
        allowClear: true,
        allowFilter: false,
        multiple: false,
        itemTemplate: (obj) => obj,
        selected: 1,
        dataSource: helloList,
        width: 210,
    }
    const select = EditableSelect(params)
    return select
}
const Select3 = () => {
    const params = {
        id: 'Select3[a]',
        name: 'Select3',
        placeholder: "Select country",
        readOnly: false,
        allowClear: false,
        allowFilter: true,
        multiple: false,
        itemTemplate: (obj) => obj,
        selected: 1,
        dataSource: helloList,
        width: 210,
    }
    const select = EditableSelect(params)
    return select
}
const MSelect1 = () => {
    const params = {
        id: 'MultiSelect1_a',
        name: 'MultiSelect1[]',
        placeholder: "Select country",
        readOnly: false,
        allowClear: false,
        allowFilter: false,
        multiple: true,
        itemTemplate: (obj) => obj,
        selected: 1,
        dataSource: helloList,
        width: 210,
    }
    const select = EditableSelect(params)
    return select
}
const MSelect2 = () => {
    const params = {
        id: 'MultiSelect2[a]',
        name: 'MultiSelect2[]',
        placeholder: "Select country",
        readOnly: false,
        allowClear: true,
        allowFilter: false,
        multiple: true,
        itemTemplate: (obj) => obj,
        selected: 1,
        dataSource: helloList,
        width: 210,
    }
    const select = EditableSelect(params)
    return select
}
const MSelect3 = () => {
    const params = {
        id: 'MultiSelect3[a]',
        name: 'MultiSelect3[]',
        placeholder: "Select country",
        readOnly: false,
        allowClear: false,
        allowFilter: true,
        multiple: true,
        itemTemplate: (obj) => obj,
        selected: 1,
        dataSource: helloList,
        width: 210,
    }
    const select = EditableSelect(params)
    return select
}

export const EditableSelectDemo = (divId) => {

    const rendererSelect = `
    <div class="grid grid-cols-12 gap-2">
        <div class="col-span-3">
            ${Select1()}
        </div>
        <div class="col-span-3">
            ${Select2()}
        </div>
        <div class="col-span-3">
            ${Select3()}
        </div>
    </div>
    `

    const rendererMultiSelect = `
    <div class="grid grid-cols-12 gap-2">
        <div class="col-span-3">
            ${MSelect1()}
        </div>
        <div class="col-span-3">
            ${MSelect2()}
        </div>
        <div class="col-span-3">
            ${MSelect3()}
        </div>
    </div>
    `


    let select = `Select: ${rendererSelect}`
    // select += `MultiSelect: ${rendererMultiSelect}`

    $("#" + divId).html(select)
}