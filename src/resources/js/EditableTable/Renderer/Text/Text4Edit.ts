import { twMerge } from 'tailwind-merge'
import { Renderer4Edit } from '../Renderer4Edit'

export class Text4Edit extends Renderer4Edit {
    control() {
        const { cellValue, tableConfig, column } = this
        const classList = twMerge(`${tableConfig.classList?.text} ${column.classList}`)

        const { controlId } = this

        const html = `<input component="text4edit" id="${controlId}" type="text" class="${classList}" value="${
            cellValue || ''
        }" />`
        return `
        ${html}
        `
    }
}
