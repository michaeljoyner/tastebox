export default {
    namespaced: true,

    state: {
        all: [
            {
                message:
                    "Act only according to that maxim whereby you can, at the same time, will that it should become a universal law.",
                author: "Immanuel Kant",
            },
            {
                message: "An unexamined life is not worth living.",
                author: "Socrates",
            },
            { message: "Be present above all else.", author: "Naval Ravikant" },
            {
                message:
                    "Happiness is not something readymade. It comes from your own actions.",
                author: "Dalai Lama",
            },
            { message: "He who is contented is rich.", author: "Laozi" },
            {
                message:
                    "I begin to speak only when I am certain what I will say is not better left unsaid",
                author: "Cato the Younger",
            },
            {
                message:
                    "If you do not have a consistent goal in life, you can not live it in a consistent way.",
                author: "Marcus Aurelius",
            },
            {
                message:
                    "It is not the man who has too little, but the man who craves more, that is poor.",
                author: "Seneca",
            },
            {
                message: "It is quality rather than quantity that matters.",
                author: "Lucius Annaeus Seneca",
            },
            {
                message:
                    "Knowing is not enough; we must apply. Being willing is not enough; we must do.",
                author: "Leonardo da Vinci",
            },
            {
                message:
                    "Let all your things have their places; let each part of your business have its time.",
                author: "Benjamin Franklin",
            },
            {
                message: "No surplus words or unnecessary actions.",
                author: "Marcus Aurelius",
            },
            {
                message: "Order your soul. Reduce your wants.",
                author: "Augustine",
            },
            {
                message:
                    "People find pleasure in different ways. I find it in keeping my mind clear.",
                author: "Marcus Aurelius",
            },
            {
                message: "Simplicity is an acquired taste.",
                author: "Katharine Gerould",
            },
            {
                message: "Simplicity is the consequence of refined emotions.",
                author: "Jean D'Alembert",
            },
            {
                message: "Simplicity is the essence of happiness.",
                author: "Cedric Bledsoe",
            },
            {
                message: "Simplicity is the ultimate sophistication.",
                author: "Leonardo da Vinci",
            },
            {
                message: "Smile, breathe, and go slowly.",
                author: "Thich Nhat Hanh",
            },
            {
                message:
                    "The only way to do great work is to love what you do.",
                author: "Steve Jobs",
            },
            {
                message:
                    "The whole future lies in uncertainty: live immediately.",
                author: "Seneca",
            },
            {
                message: "Very little is needed to make a happy life.",
                author: "Marcus Antoninus",
            },
            {
                message:
                    "Waste no more time arguing what a good man should be, be one.",
                author: "Marcus Aurelius",
            },
            { message: "Well begun is half done.", author: "Aristotle" },
            {
                message: "When there is no desire, all things are at peace.",
                author: "Laozi",
            },
            {
                message: "Walk as if you are kissing the Earth with your feet.",
                author: "Thich Nhat Hanh",
            },
            {
                message: "Because you are alive, everything is possible.",
                author: "Thich Nhat Hanh",
            },
            {
                message:
                    "Breathing in, I calm body and mind. Breathing out, I smile.",
                author: "Thich Nhat Hanh",
            },
            {
                message: "Life is available only in the present moment.",
                author: "Thich Nhat Hanh",
            },
            {
                message:
                    "The best way to take care of the future is to take care of the present moment.",
                author: "Thich Nhat Hanh",
            },
        ],
    },

    getters: {
        random: (state) =>
            state.all[Math.floor(Math.random() * state.all.length)],
    },
};
