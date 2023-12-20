export const UserColumns = [
    {
        dataIndex: "no",
        title: "No.",
        renderer: '_no_',
    },
    {
        dataIndex: "action",
        title: "Action",
        renderer: 'action',
        control: 'action',
    },
    {
        dataIndex: "name",
        title: "Name",
        renderer: 'text',
        control: 'text',
        footer: 'agg_sum',
    },
    {
        dataIndex: "is_female",
        title: "Is Female",
        renderer: 'toggle',
        control: 'toggle',
        footer: 'agg_count',
    },
    {
        dataIndex: "is_resigned",
        title: "Is Resigned",
        renderer: 'toggle',
        control: 'toggle',
        footer: 'A Text',
    },
    {
        dataIndex: "hidden",
        title: "Hidden Column",
        renderer: 'text',
        control: 'text',
        hidden: true,
    },
    {
        dataIndex: "no_renderer",
        title: "No Renderer",
        footer: 'agg_sum',
    },
]