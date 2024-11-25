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

    setValueToTableData(value?: TableCellType): void {
        const { tableName, rowIndex, dataIndex } = this
        const control = document.getElementById(this.controlId) as HTMLInputElement
        const cellValue = value !== undefined ? value : (control.value as unknown as TableCellType)
        const before = tableData[tableName].data[rowIndex][dataIndex]
        tableData[tableName].data[rowIndex][dataIndex] = cellValue
        console.log('setValueToTableData', tableName, rowIndex, dataIndex, before, '==>', cellValue)
    }

    render(): TableRenderedValueObject {
        return {
            rendered: this.control(),
            tdClass: this.tdClass,
            divClass: this.divClass,
            applyPostRenderScript: this.applyPostRenderScript.bind(this),
            applyOnMouseMoveScript: this.applyOnMouseMoveScript.bind(this),
            applyOnChangeScript: this.applyOnChangeScript.bind(this),
        }
    }
}
