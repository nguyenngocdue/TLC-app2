import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class Text4View {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        const { cellValue } = this.params
        return {
            rendered: cellValue as unknown as string,
            classStr: this.params.column.classList || '',
        }
    }
}
