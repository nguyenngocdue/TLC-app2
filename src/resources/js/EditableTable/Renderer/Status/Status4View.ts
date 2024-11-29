import { getDataSourceFromK } from '../../Functions/CacheKByKey'
import { Renderer4View } from '../Renderer4View'

export class Status4View extends Renderer4View {
    protected tdClass: string = 'text-center'
    control() {
        const { cellValue } = this
        const value = cellValue as unknown as string

        const statuses = getDataSourceFromK('statuses', 'name')
        const status = statuses[value] || {}
        // console.log('Status4View.render', status)
        const { text_color = 'gray', bg_color = 'pink' } = status

        const classList = `bg-${text_color} text-${bg_color} hover:bg-${bg_color} hover:text-${text_color} rounded whitespace-nowrap font-semibold text-xs-vw text-xs mx-0.5 px-2 py-1 leading-7 `
        const rendered = `<span class="${classList}" title="${value}">${status.title}</span>`

        return rendered
    }
}
