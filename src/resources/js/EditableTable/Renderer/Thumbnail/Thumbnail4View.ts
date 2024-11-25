import { getForeignObjects } from '../../Functions'
import { TableColumnThumbnail } from '../../Type/EditableTable3ColumnType'
import { DataSourceItem, TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Thumbnail4View extends Renderer4View {
    static renderThumbnailBox(item?: DataSourceItem | string): HTMLElement {
        const classList = `h-10 w-10 p-1 rounded border`

        if (item) {
            if (typeof item === 'string') {
                const parentControlId = item

                // Create the container div
                const container = document.createElement('div')
                container.className =
                    'relative w-full bg-gray-100 border border-gray-300 rounded h-10'

                // Create the progress bar
                const progressBar = document.createElement('div')
                progressBar.id = `${parentControlId}__progress_bar`
                progressBar.style.width = '0%'
                progressBar.style.top = '80%'
                progressBar.className =
                    'relative bg-green-500 h-1/5 rounded transition-all duration-200 ease-linear'
                container.appendChild(progressBar)

                // Create the spinner icon
                const spinner = document.createElement('i')
                spinner.className =
                    'fa-duotone fa-spinner fa-spin text-green-500 absolute top-1/4 left-1/4 transform -translate-x-1/4 -translate-y-1/4'
                container.appendChild(spinner)

                return container
            } else {
                const { src, name = 'no-name' } = item

                // Create the image element
                const img = document.createElement('img')
                img.src = src.toString()
                img.className = classList
                img.alt = name.toString()

                return img
            }
        } else {
            // Fallback case if no item is provided
            const placeholder = document.createElement('div')
            placeholder.textContent = 'item??'
            return placeholder
        }
    }

    render(): TableRenderedValueObject {
        const cellValue = this.cellValue as unknown as DataSourceItem
        const column = this.column as TableColumnThumbnail

        const { maxToShow = 8, maxPerLine = 4 } = column.rendererAttrs || {}
        const merged = getForeignObjects(cellValue)

        const imgs = merged
            .slice(0, maxToShow)
            .map((item) => Thumbnail4View.renderThumbnailBox(item).outerHTML)
            .join('')
        const more = merged.length - maxToShow
        const moreDiv = more > 0 ? `<div class="p-1 rounded-full border ">+${more}</div>` : ``
        const rendered = `<div>
            <div class="flex items-center justify-center gap-1">
                <div id="${this.controlId}_thumbnail_div" class="grid grid-cols-${maxPerLine}">
                    ${imgs}
                </div>
                ${moreDiv}
            </div>
        </div>`

        return { rendered, tdClass: 'text-center' }
    }
}
