import { twMerge } from 'tailwind-merge'
import { TableColumnNumber } from '../../Type/EditableTable3ColumnType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Number4Edit extends Renderer4Edit {
    applyOnChangeScript(): void {
        const control = document.getElementById(this.controlId) as HTMLInputElement
        control && control.addEventListener('change', () => this.setValueToTableData())
    }
    control() {
        const { cellValue, controlId, tableConfig } = this
        const column = this.column as TableColumnNumber
        const { decimalPlaces = 0 } = column.rendererAttrs || {}
        const classList = twMerge(`text-right`, tableConfig.classList?.text)

        const value = cellValue ? ((cellValue as unknown as number) * 1).toFixed(decimalPlaces) : ''
        const step = Math.pow(10, -decimalPlaces)
        // console.log('step', step, decimalPlaces)

        const html = `<input 
            component="text4edit" 
            id="${controlId}"
            class="${classList}" 
            value="${value}" 
            type="number" 
            step="${step}" 
            />`
        return html
    }
}
