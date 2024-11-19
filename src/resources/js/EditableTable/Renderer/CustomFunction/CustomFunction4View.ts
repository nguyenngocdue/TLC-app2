import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class CustomFunction4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const { customRenderFn } = this
        if (customRenderFn) {
            return customRenderFn()
        }
        return { rendered: 'custom renderer function is undefined' }
    }
}
