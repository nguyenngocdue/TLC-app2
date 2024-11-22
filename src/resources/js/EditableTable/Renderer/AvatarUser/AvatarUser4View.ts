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
                if (!item) return ''
                const classList = `h-10 w-10 rounded-full p-1`
                const img = `<img src="${item.src}" class="${classList}" alt="${item.name}" />`
                const tooltip = Str.makeId(item.id)
                return `<div class="flex items-center border rounded" title="${tooltip}">
                    ${img} 
                    <span class="font-semibold">
                        ${item.name}
                    </span>
                </div>`
            })
            .join('')
        const more = merged.length - maxToShow
        const moreTitle = merged
            .slice(maxToShow)
            .map((item) => `${Str.makeId(item.id)} - ${item.name}`)
            .join('\n')
        const moreClass = `p-1 rounded-full border cursor-pointer`
        const moreSpan =
            more > 0 ? `<span class="${moreClass}" title="${moreTitle}">+${more}</span>` : ``
        const gridClass = `grid grid-cols-${merged.length > 1 ? 2 : 1}`
        const widthClass = `${merged.length > 1 ? 'w-11/12' : 'w-full'}`
        const rendered = `<div class="flex items-center gap-1">
                <div class="${gridClass} ${widthClass}">
                    ${divs}
                </div>
                <div>
                    ${moreSpan}
                </div>
            </div>`
        return { rendered, tdClass: 'text-center' }
    }
}
