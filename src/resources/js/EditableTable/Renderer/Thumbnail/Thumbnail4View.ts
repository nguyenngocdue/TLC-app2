import { getForeignObjects } from '../../Functions'
import { TableColumnThumbnail } from '../../Type/EditableTable3ColumnType'
import { DataSourceItem, TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Thumbnail4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const cellValue = this.cellValue as unknown as DataSourceItem
        const column = this.column as TableColumnThumbnail

        const { maxToShow = 8, maxPerLine = 4 } = column.rendererAttrs || {}
        const merged = getForeignObjects(cellValue)

        const imgs = merged
            .slice(0, maxToShow)
            .map((item) => {
                if (item) {
                    const src = item.src
                    const classList = `h-10 w-10 p-1 rounded border`
                    return `<img src="${src}" class="${classList}" alt="${item.name}" />`
                } else {
                    return 'item is undefined'
                }
            })
            .join('')
        const more = merged.length - maxToShow
        const moreDiv = more > 0 ? `<div class="p-1 rounded-full border ">+${more}</div>` : ``
        const rendered = `<div>
            <div class="flex items-center justify-center gap-1">
                <div class="grid grid-cols-${maxPerLine}">${imgs}</div>
                ${moreDiv}
            </div>
        </div>`

        return { rendered, tdClass: 'text-center' }
    }
}
