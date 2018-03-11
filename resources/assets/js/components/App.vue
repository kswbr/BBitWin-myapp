<template>
    <div class="container">
        <div class="row">

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">ROOT COMPONENT !!!</div>

                    <div class="panel-body">
                        I'm an example component!!!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Axios from 'axios'

    export default {
        mounted() {
            console.log('Component mounted!!.')
            Axios.get('/api/user').then((res) =>  {
                console.log(res)
            })
        }
    }
</script>
