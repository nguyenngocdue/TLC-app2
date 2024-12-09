import { Renderer4View } from '../Renderer4View'

export class CustomFunction4View extends Renderer4View {
    control() {
        const { customRenderFn } = this
        if (customRenderFn) {
            return customRenderFn()
        }
        return 'custom renderer function is undefined'
    }
}
