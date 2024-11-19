import { getForeignObjects } from '../../Functions'
import { TableColumnThumbnail } from '../../Type/EditableTable3ColumnType'
import { DataSourceItem, TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Thumbnail4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const cellValue = this.cellValue as unknown as DataSourceItem
        const column = this.column as TableColumnThumbnail

        const { maxToShow = 5 } = column.rendererAttrs || {}
        const merged = getForeignObjects(cellValue)

        const imgs = merged
            .slice(0, maxToShow)
            .map((item) => {
                const src = item.src
                const classList = `h-10 w-10 p-1 rounded`
                return `<img src="${src}" class="${classList}" alt="${item.name}" />`
            })
            .join('')
        const more = merged.length - maxToShow
        const rendered = `<div>
            <div class="flex items-center justify-center">
                ${imgs}
            </div>
            ${more > 0 ? `And ${more} more` : ``}
        </div>`

        return { rendered, tdClass: 'text-center' }
    }
}
