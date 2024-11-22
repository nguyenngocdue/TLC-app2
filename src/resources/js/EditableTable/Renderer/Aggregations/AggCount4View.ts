import { getForeignObjects, Str } from '../../Functions'
import { TableColumnAggCount } from '../../Type/EditableTable3ColumnType'
import { DataSourceItem, TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class AggCount4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const cellValue = this.cellValue as unknown as DataSourceItem
        const column = this.column as TableColumnAggCount
        const { unit = 'item', columnToLoad = 'name' } = column.rendererAttrs || {}

        const merged = getForeignObjects(cellValue)
        const { length } = merged
        // console.log(cellValue, merged, length)

        const units = Str.pluralize(unit, length)
        const rendered = length ? `${length} ${units}` : ``

        let titles = ''
        if (columnToLoad) {
            titles = merged
                .map((item) => (item && item[columnToLoad] ? item[columnToLoad] : ''))
                .join(', ')
        }

        return { rendered, tdClass: 'text-center', tdTooltip: titles }
    }
}
