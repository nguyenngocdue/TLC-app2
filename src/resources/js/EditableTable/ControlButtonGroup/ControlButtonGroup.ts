import { TableParams } from '../Type/EditableTable3ParamType'
import { onClickAddAnItem } from './onClickAddAnItem'

const createButton = (
    id: string,
    className: string,
    iconClass: string,
    label: string,
): HTMLButtonElement => {
    const button = document.createElement('button')
    button.id = id
    button.type = 'button'
    button.className = className

    const icon = document.createElement('i')
    icon.className = iconClass

    button.appendChild(icon)
    button.appendChild(document.createTextNode(` ${label}`))

    return button
}

export const ControlButtonGroupElement = (params: TableParams): HTMLDivElement => {
    const showButton = params.tableConfig.showButton
    if (!showButton) return document.createElement('div')

    const classList = (primaryColor: string = 'green') =>
        `bg-${primaryColor}-500 hover:bg-${primaryColor}-700 items-center text-white font-bold px-2 py-0.5 text-sm rounded`

    const container = document.createElement('div')
    container.className = 'flex items-center justify-between m-1'

    const leftDiv = document.createElement('div')
    leftDiv.className = 'flex gap-1'

    // Add buttons to the left div
    if (showButton.AddAnItem) {
        const btn = createButton(
            `${params.tableName}__btn_add_an_item`,
            classList(),
            'fa fa-plus',
            'Add An Item',
        )
        btn.addEventListener('click', () => onClickAddAnItem(params))
        leftDiv.appendChild(btn)
    }

    if (showButton.AddFromList) {
        leftDiv.appendChild(
            createButton(
                `${params.tableName}__btn_add_from_list`,
                classList(),
                'fa-duotone fa-window',
                'Add From List',
            ),
        )
    }

    if (showButton.CloneFromTemplate) {
        leftDiv.appendChild(
            createButton(
                `${params.tableName}__btn_clone_from_template`,
                classList(),
                'fa fa-copy',
                'Clone From Template',
            ),
        )
    }
    container.appendChild(leftDiv)

    const rightDiv = document.createElement('div')
    rightDiv.className = 'flex gap-1'

    // Add buttons to the right div
    if (showButton.Recalculate) {
        rightDiv.appendChild(
            createButton(
                `${params.tableName}__btn_recalculate`,
                classList('blue'),
                'fa-duotone fa-calculator',
                'Recalculate',
            ),
        )
    }

    container.appendChild(rightDiv)

    return container
}
