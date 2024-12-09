// import { TableColumnCheckbox } from '../../Type/EditableTable3ColumnType'
import { LengthAware, TableCellType } from '../../Type/EditableTable3DataLineType'
import { Renderer4Edit } from '../Renderer4Edit'

declare let tableData: { [tableName: string]: LengthAware }

export class Checkbox4Edit extends Renderer4Edit {
    protected tdClass = 'text-center'
    applyOnChangeScript(): void {
        const input = document.getElementById(this.controlId) as HTMLInputElement
        if (!input) return
        input.addEventListener('change', () => {
            this.setValueToTableData(input.checked as unknown as TableCellType)
            if (this.column.dataIndex == '_checkbox_for_line_') {
                console.log('checkbox for line')
                //get all value of checkbox for line, if any is true, show the master button group
                const dataSource = tableData[this.tableName]
                const isAnyChecked = dataSource.data.some((data) => data._checkbox_for_line_)
                const masterButtonGroup = document.getElementById(
                    `${this.tableName}__master_button_group`,
                )
                if (masterButtonGroup) {
                    masterButtonGroup.classList.toggle('hidden', !isAnyChecked)
                }
            }
        })
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
