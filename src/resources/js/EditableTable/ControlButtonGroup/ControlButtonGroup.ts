import { TableParams } from '../Type/EditableTable3ParamType'

export const ControlButtonGroup = (params: TableParams) => {
    const showButton = params.tableConfig.showButton
    if (!showButton) return ''

    const classList = (primaryColor: string = 'green') =>
        `bg-${primaryColor}-500 hover:bg-${primaryColor}-700 text-white font-bold px-2 py-0.5 text-sm rounded`
    const btnAddAnItem =
        showButton.AddAnItem &&
        `<button 
            type="button"
            class="${classList()}"
            >
            Add An Item
    </button>`

    const btnAddFromList =
        showButton.AddFromList &&
        `<button 
            type="button"
            class="${classList()}"
            >
            Add From List
    </button>`

    const btnCloneFromTemplate =
        showButton.CloneFromTemplate &&
        `<button 
            type="button"
            class="${classList()}"
            >
            Clone From Template
    </button>`

    const btnRecalculate =
        showButton.Recalculate &&
        `<button 
            type="button"
            class="${classList('blue')}"
            >
            Recalculate
    </button>`

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
