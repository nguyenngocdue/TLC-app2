import { twMerge } from 'tailwind-merge'
import { Renderer4Edit } from '../Renderer4Edit'

// declare let tableData: { [tableName: string]: LengthAware }

export class Text4Edit extends Renderer4Edit {
    applyOnChangeScript(): void {
        const control = document.getElementById(this.controlId) as HTMLInputElement
        control && control.addEventListener('change', () => this.setValueToTableData())
    }
    control() {
        const { tableConfig, column } = this
        const classList = twMerge(`${tableConfig.classList?.text} ${column.classList}`)
        const cellValue = this.cellValue

        const { controlId } = this

        const html = `<input 
            component="text4edit" 
            id="${controlId}" 
            type="text" 
            class="${classList}" 
            value="${cellValue}"
        />`
        return html
    }
}
