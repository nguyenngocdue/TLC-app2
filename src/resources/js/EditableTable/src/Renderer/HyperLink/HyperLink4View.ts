import { twMerge } from 'tailwind-merge'
import { Renderer4View } from '../Renderer4View'
import { TableColumnHyperLink } from '../../Type/EditableTable3ColumnType'

export class HyperLink4View extends Renderer4View {
    protected tdClass: string = `text-center`

    protected divStyle: { [key: string]: string | number } = { width: '60px' }
    control() {
        const { cellValue, tableConfig } = this
        const column = this.column as TableColumnHyperLink
        const rendererAttrs = column.rendererAttrs || {}
        const { target = '_blank' } = rendererAttrs

        const classList = twMerge(
            tableConfig.classList?.button,
            `text-xs text-xs-vw font-semibold text-white bg-blue-500 hover:bg-blue-700 py-1 px-2 rounded`,
        )

        const value = cellValue
            ? `<a href="${cellValue}" target="${target}" ><button class="${classList}">Open</button></a>`
            : ``
        return value
    }
}
