<template>
    <page>
        <page-header title="Meal Popularity">
            <input-field
                v-model="query"
                placeholder="Search by name"
            ></input-field>
        </page-header>

        <div class="my-12">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Meal
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Times Available
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Total Kits
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Total Servings
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Last Available
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="meal in meals" :key="meal.id" class="">
                        <td class="p-2 text-sm">
                            <router-link
                                :to="`/meals/${meal.id}/manage/info`"
                                >{{ meal.name }}</router-link
                            >
                        </td>
                        <td class="p-2 text-sm">{{ meal.times_offered }}</td>
                        <td class="p-2 text-sm">{{ meal.total_ordered }}</td>
                        <td class="p-2 text-sm">{{ meal.total_servings }}</td>
                        <td class="p-2 text-sm whitespace-nowrap">
                            {{ meal.last_offered_ago }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import InputField from "../../Components/Forms/InputField";
export default {
    components: { InputField, PageHeader, Page },

    setup() {
        const store = useStore();

        const query = ref("");

        const meals = computed(() => {
            return store.getters["meals/byPopularity"].filter((m) => {
                if (query.length < 3) {
                    return true;
                }
                return m.name.toLowerCase().includes(query.value.toLowerCase());
            });
        });

        onMounted(() => {
            store.dispatch("meals/fetchMeals");
        });

        return { meals, query };
    },
};
</script>
