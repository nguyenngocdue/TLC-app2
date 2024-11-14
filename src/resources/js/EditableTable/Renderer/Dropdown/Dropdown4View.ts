import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Dropdown4View {
    constructor(private params: TableRendererParams) {}

    render() {
        return { rendered: 'Dropdown 4 View', classStr: this.params.column.classList || '' }
    }
}
