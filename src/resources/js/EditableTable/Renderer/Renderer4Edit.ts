import {
    LengthAware,
    TableCellType,
    TableRenderedValueObject,
} from '../Type/EditableTable3DataLineType'
import { Renderer4View } from './Renderer4View'

declare let tableData: { [tableName: string]: LengthAware }

export abstract class Renderer4Edit extends Renderer4View {
    abstract control(): string
    applyOnChangeScript(): void {}

    setValueToTableData(): void {
        const { tableName, rowIndex, dataIndex } = this
        const control = document.getElementById(this.controlId) as HTMLInputElement
        tableData[tableName].data[rowIndex][dataIndex] = control.value as unknown as TableCellType
        console.log('setValueToTableData', tableName, rowIndex, dataIndex, ':=', control.value)
    }

    render(): TableRenderedValueObject {
        return {
            rendered: this.control(),
            applyPostRenderScript: this.applyPostRenderScript.bind(this),
            applyOnMouseMoveScript: this.applyOnMouseMoveScript.bind(this),
            applyOnChangeScript: this.applyOnChangeScript.bind(this),
        }
    }
}
