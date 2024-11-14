import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class Text4Edit {
    private tableDebug = false

    constructor(private params: TableRendererParams) {
        // this.tableDebug = this.params.params.tableConfig.tableDebug || false
    }

    control() {
        return `aaaaaa`
    }

    render(): TableRenderedValueObject {
        const control = this.control()
        return { rendered: control, classStr: this.params.column.classList || '' }
    }
}
