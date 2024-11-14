import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class Number4View {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        const { cellValue } = this.params
        return {
            rendered: cellValue.toString(),
            classStr: this.params.column.classList || '',
        }
    }
}
