import { TableCellType } from '../../Type/EditableTable3DataLineType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Toggle4Edit extends Renderer4Edit {
    applyOnChangeScript(): void {
        const input = document.getElementById(this.controlId) as HTMLInputElement
        input &&
            input.addEventListener('change', () =>
                this.setValueToTableData(input.checked as unknown as TableCellType),
            )
    }
    control() {
        const { controlId, tableConfig, cellValue } = this
        const classList = tableConfig.classList?.toggle

        const checked = !!cellValue ? 'checked' : ''
        return `<div class="flex justify-center">
            <label for="${controlId}" class="inline-flex relative items-center cursor-pointer">
                <input type="checkbox" id="${controlId}" class="sr-only peer" ${checked} value="true">
                <div class="${classList}"></div>
            </label>
        </div>`
    }
}
