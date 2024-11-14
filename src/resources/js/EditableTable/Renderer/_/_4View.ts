import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class Text4View {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        return { rendered: 'Text4 View', classStr: this.params.column.classList || '' }
    }
}
