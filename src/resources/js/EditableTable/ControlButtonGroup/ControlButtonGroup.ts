import { TableParams } from '../Type/EditableTable3ParamType'

export const ControlButtonGroup = (params: TableParams) => {
    const showButton = params.tableConfig.showButton
    if (!showButton) return ''

    const btnAddAnItem =
        showButton.AddAnItem &&
        `<button class="bg-green-500 hover:bg-green-700 text-white font-bold px-2 py-0.5 text-sm rounded">Add An Item</button>`

    const btnAddFromList =
        showButton.AddFromList &&
        `<button class="bg-green-500 hover:bg-green-700 text-white font-bold px-2 py-0.5 text-sm rounded">Add From List</button>`

    const btnCloneFromTemplate =
        showButton.CloneFromTemplate &&
        `<button class="bg-green-500 hover:bg-green-700 text-white font-bold px-2 py-0.5 text-sm rounded">Clone From Template</button>`

    const btnRecalculate =
        showButton.Recalculate &&
        `<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-0.5 text-sm rounded">Recalculate</button>`

    return `<div class="flex items-center justify-between m-1">
                <div>
                    ${btnAddAnItem}
                    ${btnAddFromList}
                    ${btnCloneFromTemplate}
                </div>
                <div>
                    ${btnRecalculate}
                </div>
        </div>`
}
