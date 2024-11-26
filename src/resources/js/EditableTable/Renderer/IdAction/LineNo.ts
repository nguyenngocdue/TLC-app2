import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class LineNo extends Renderer4View {
    render(): TableRenderedValueObject {
        const rowIndex = this.rowIndex
        const rendered = (rowIndex + 1).toString()
        const divClass = this.tableConfig.orderable ? `drag-handle cursor-pointer` : ``

        return {
            rendered,
            divClass,
            tdClass: `text-center`,
        }
    }
}
