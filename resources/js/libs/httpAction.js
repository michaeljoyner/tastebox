import { ref } from "vue";

const httpAction = (action, success, fail) => {
    const busy = ref(false);

    const handle = (...args) => {
        busy.value = true;

        return action(...args)
            .then((resp) => {
                success(resp);
            })
            .catch((err) => {
                fail(err);
            })
            .then(() => (busy.value = false));
    };

    return [busy, handle];
};

export { httpAction };
