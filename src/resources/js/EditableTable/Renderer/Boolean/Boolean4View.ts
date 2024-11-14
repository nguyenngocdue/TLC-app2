import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Boolean4View {
    constructor(private params: TableRendererParams) {}

    render() {
        const { cellValue } = this.params
        const value = cellValue ? `<i class="fas fa-circle-check text-green-500 text-lg"></i>` : ``
        return { rendered: value, classStr: 'text-center' }
    }
}
