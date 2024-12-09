import { DataSourceItem } from '../../Type/EditableTable3DataLineType'
import { TableColumnColumn } from '../../Type/EditableTable3ColumnType'
import { getForeignObjects } from '../../Functions'

export const renderColumn4 = (
    column: TableColumnColumn,
    cellValue: DataSourceItem,
    allowOpen: boolean,
) => {
    // const column = column as TableColumnColumn
    const rendererAttrs = column.rendererAttrs || {}
    const { columnToLoad = 'name' } = rendererAttrs

    const merged = getForeignObjects(cellValue)
    // console.log(merged)

    let arrayOfRendered: string[] = []
    if (columnToLoad) {
        arrayOfRendered = merged.map((foreignObject) => {
            if (foreignObject && foreignObject[columnToLoad]) {
                const s = foreignObject[columnToLoad] as string
                if (allowOpen) {
                    const href = foreignObject.href || 'define-href-in-the-cell'
                    return `<a href="${href}" target="_blank">
                    <button class="text-xs text-xs-vw font-semibold rounded px-2 py-1 bg-blue-500 hover:bg-blue-700 text-white">${s}</button>
                </a>`
                } else return `${s}`
            }
            return ''
        })
    }
    const rendered = arrayOfRendered.join(`${allowOpen ? ' ' : ', '}`)
    return rendered
}
