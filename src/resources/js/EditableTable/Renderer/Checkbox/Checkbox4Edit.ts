// import { TableColumnCheckbox } from '../../Type/EditableTable3ColumnType'
import { TableCellType } from '../../Type/EditableTable3DataLineType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Checkbox4Edit extends Renderer4Edit {
    protected tdClass = 'text-center'
    applyOnChangeScript(): void {
        const input = document.getElementById(this.controlId) as HTMLInputElement
        input &&
            input.addEventListener('change', () =>
                this.setValueToTableData(input.checked as unknown as TableCellType),
            )
    }
    control() {
        const { controlId, tableConfig, cellValue } = this
        const classList = tableConfig.classList?.toggle_checkbox
        // const column = this.column as TableColumnCheckbox
        // console.log('column', column)
        const checked = !!cellValue ? 'checked' : ''
        return `<input id="${controlId}" type="checkbox" class="${classList}" ${checked} value="true"/>`
    }
}
