import { DataSourceItem } from '../../Type/EditableTable3DataLineType'
import { TableColumnIdStatus } from '../../Type/EditableTable3ColumnType'
import { Str } from '../../Function/Functions'
import { getDataSourceFromK } from '../../Function/CacheKByKey'

export const renderColumn4 = (
    column: TableColumnIdStatus,
    cellValue: DataSourceItem,
    allowOpen: boolean,
) => {
    // const column = column as TableColumnColumn
    const rendererAttrs = column.rendererAttrs || {}
    const { statusColumn = 'status', nameColumn = 'name' } = rendererAttrs
    const foreignObject = cellValue as unknown as DataSourceItem
    const foreignObjects = cellValue as unknown as DataSourceItem[]
    // console.log('Column4View.render', foreignObject, foreignObjects, columnToLoad)

    const merged = !Array.isArray(foreignObjects) ? [foreignObject] : foreignObjects
    // console.log(merged)

    const renderStatus = (foreignObject: DataSourceItem) => {
        const idKey = foreignObject['id']
        const idStr = Str.makeId(idKey)
        const tooltip = foreignObject[nameColumn] as string

        const statusKey = foreignObject[statusColumn] as string
        const statuses = getDataSourceFromK('statuses', 'name')
        // console.log('renderStatus', statuses, statusKey)

        const status = statuses[statusKey]
        const { text_color, bg_color } = status
        const classList = `bg-${text_color} text-${bg_color} hover:bg-${bg_color} hover:text-${text_color} rounded whitespace-nowrap font-semibold text-xs-vw text-xs mx-0.5 px-2 py-1 leading-7 `
        return `<span 
            class="${classList}"
            title="${tooltip}"
            >${idStr}</span>`
    }

    let arrayOfRendered: string[] = []
    arrayOfRendered = merged.map((foreignObject) => {
        const statusKey = foreignObject[statusColumn] as string

        if (!statusKey) return ''
        const rendered = renderStatus(foreignObject)
        if (!allowOpen) return `${rendered}`

        const href = foreignObject.href || 'define-href-in-the-cell'
        return `<a href="${href}" target="_blank">${rendered}</a>`
    })
    const rendered = arrayOfRendered.join(` `)
    const tdClass = `whitespace-nowrap`
    return { rendered, tdClass }
}
