import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Dropdown4Edit {
    constructor(private params: TableRendererParams) {}

    render() {
        return { rendered: 'Dropdown 4 Edit', classStr: this.params.column.classList || '' }
    }
}
