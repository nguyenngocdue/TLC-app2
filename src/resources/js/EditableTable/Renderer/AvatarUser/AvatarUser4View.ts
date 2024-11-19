import { getForeignObjects, Str } from '../../Functions'
import { TableColumnAvatarUser } from '../../Type/EditableTable3ColumnType'
import { DataSourceItem, TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class AvatarUser4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const cellValue = this.cellValue as unknown as DataSourceItem
        const column = this.column as TableColumnAvatarUser
        const { maxToShow = 4 } = column.rendererAttrs || {}

        const merged = getForeignObjects(cellValue)

        const divs = merged
            .slice(0, maxToShow)
            .map((item) => {
                const classList = `h-8 w-8 rounded-full p-1`
                const img = `<img src="${item.src}" class="${classList}" alt="${item.name}" />`
                const tooltip = Str.makeId(item.id)
                return `<div class="flex items-center border" title="${tooltip}">
                    ${img} 
                    <span class="font-semibold">
                        ${item.name}
                    </span>
                </div>`
            })
            .join('')
        const more = merged.length - maxToShow
        const rendered = `<div class="">
                <div class="grid grid-cols-2">
                    ${divs}
                </div>
                ${more > 0 ? `And ${more} more` : ``}
            </div>`
        return { rendered, tdClass: 'text-center' }
    }
}
