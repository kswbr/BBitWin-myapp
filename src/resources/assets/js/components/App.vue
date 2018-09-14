<template>
  <div class="container" >
    <router-view />
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { Message } from 'element-ui'
import _ from 'lodash'

export default {
  computed: {
    ...mapState(['inRequest','errors','success'])
  },
  mounted () {
  },
  watch: {
    errors: {
      handler: function () {
        if (!_.isEmpty(this.errors)) {
            let messageLabel = ""
            _.each(this.errors.messages, (data) => {
                _.each(data, (message) => {
                    messageLabel += "<p style='margin:10px;'>" + message + "</p>"
                })
            })
            this.$message.error({
                dangerouslyUseHTMLString: true,
                message: messageLabel,
                duration: 0,
                showClose: true
            });
        }
      },
      deep: true
    },
    success: {
      handler: function () {
        if (!_.isEmpty(this.success)) {
            let messageLabel = this.success.message
            this.$message.success({
                dangerouslyUseHTMLString: true,
                message: messageLabel,
                duration: 0,
                showClose: true
            });
        }
      }
    }
  }
}
</script>

<style>
body {
    margin:0;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}

.el-button {
  margin-top: 10px !important;
  &:last-child {
    margin-bottom: 0;
  }
}

</style>
