import { Renderer4View } from '../Renderer4View'

export class ActionCheckbox extends Renderer4View {
    protected divClass: string = 'mx-auto'
    protected divStyle: { [key: string]: string } = { width: '30px' }
    protected tdClass: string = 'text-center'

    applyPostRenderScript(): void {
        //add event listener
        const { tableName, rowIndex } = this
        const checkbox = document.getElementById(
            `${tableName}__cb__${rowIndex}`,
        ) as HTMLInputElement
        console.log('checkbox', checkbox)
        checkbox.addEventListener('change', () => this.applyOnChangeScript.bind(this))
    }

    applyOnChangeScript(): void {
        console.log('ActionCheckbox.applyOnChangeScript')
    }

    control() {
        const { tableName, rowIndex } = this

        return `<input id="${tableName}__cb__${rowIndex}" type="checkbox" />`
    }
}
