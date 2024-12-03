import { TableParams } from '../Type/EditableTable3ParamType'

export const TableConfigDiv = (params: TableParams) => {
    return `<ul>
        ${Object.entries(params.tableConfig)
            .filter(([_, value]) => value)
            .filter(([key, _]) => key !== 'classList')
            .map(([key, value]) => {
                if (typeof value === 'object') {
                    const items = Object.entries(value)
                        .filter(([_, value]) => value)
                        .map(([key, value]) => {
                            return `<li class="ml-20">${key}: ${JSON.stringify(value)}</li>`
                        })
                    return `<li class="ml-10">${key}:</li><ul>${items.join('')}</ul>`
                } else return `<li class="ml-10">${key}: ${value}</li>`
            })
            .join('')}
    </ul>`
}
