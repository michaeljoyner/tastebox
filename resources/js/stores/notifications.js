export default {
    namespaced: true,

    state: {
        notifications: [],
    },

    mutations: {
        addSuccess(state, message) {
            state.notifications.push({
                type: "success",
                message,
                confirm: false,
                timestamp: new Date().getTime(),
            });
        },

        addError(state, message) {
            state.notifications.push({
                type: "error",
                message,
                confirm: true,
                timestamp: new Date().getTime(),
            });
        },

        addWarning(state, message) {
            state.notifications.push({
                type: "warning",
                message,
                confirm: false,
                timestamp: new Date().getTime(),
            });
        },

        clear(state, notification) {
            state.notifications = state.notifications.filter((queued) => {
                return (
                    queued.timestamp !== notification.timestamp &&
                    queued.message !== notification.message
                );
            });
        },
    },
};
