export const UserColumns = [
    {
        dataIndex: "no",
        title: "No.",
        renderer: '_no_',
        // control: '_no_',
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
    },
    {
        dataIndex: "is_female",
        title: "Is Female",
        renderer: 'toggle',
        control: 'toggle',
    },
    {
        dataIndex: "hidden",
        title: "Hidden Column",
        renderer: 'text',
        control: 'text',
        hidden: true,
    },
    {
        dataIndex: "no_control",
        title: "No Control",
    },
]