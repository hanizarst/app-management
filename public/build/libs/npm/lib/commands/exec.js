const { resolve } = require('path')
const libexec = require('libnpmexec')
const BaseCommand = require('../base-command.js')

class Exec extends BaseCommand {
  static description = 'Run a command from a local or remote npm package'
  static params = [
    'package',
    'call',
    'workspace',
    'workspaces',
    'include-workspace-root',
  ]

  static name = 'exec'
  static usage = [
    '-- <pkg>[@<version>] [args...]',
    '--package=<pkg>[@<version>] -- <cmd> [args...]',
    '-c \'<cmd> [args...]\'',
    '--package=foo -c \'<cmd> [args...]\'',
  ]

  static workspaces = true
  static ignoreImplicitWorkspace = false
  static isShellout = true

  async exec (args) {
    return this.callExec(args)
  }

  async execWorkspaces (args) {
    await this.setWorkspaces()

    for (const [name, path] of this.workspaces) {
      const locationMsg =
        `in workspace ${this.npm.chalk.green(name)} at location:\n${this.npm.chalk.dim(path)}`
      await this.callExec(args, { locationMsg, runPath: path })
    }
  }

  async callExec (args, { locationMsg, runPath } = {}) {
    // This is where libnpmexec will look for locally installed packages
    const localPrefix = this.npm.localPrefix

    // This is where libnpmexec will actually run the scripts from
    if (!runPath) {
      runPath = process.cwd()
    }

    const call = this.npm.config.get('call')
    let globalPath
    const {
      flatOptions,
      localBin,
      globalBin,
      globalDir,
    } = this.npm
    const output = this.npm.output.bind(this.npm)
    const scriptShell = this.npm.config.get('script-shell') || undefined
    const packages = this.npm.config.get('package')
    const yes = this.npm.config.get('yes')
    // --prefix sets both of these to the same thing, meaning the global prefix
    // is invalid (i.e. no lib/node_modules).  This is not a trivial thing to
    // untangle and fix so we work around it here.
    if (this.npm.localPrefix !== this.npm.globalPrefix) {
      globalPath = resolve(globalDir, '..')
    }

    if (call && args.length) {
      throw this.usageError()
    }

    return libexec({
      ...flatOptions,
      // we explicitly set packageLockOnly to false because if it's true
      // when we try to install a missing package, we won't actually install it
      packageLockOnly: false,
      // copy args so they dont get mutated
      args: [...args],
      call,
      localBin,
      locationMsg,
      globalBin,
      globalPath,
      output,
      packages,
      path: localPrefix,
      runPath,
      scriptShell,
      yes,
    })
  }
}

module.exports = Exec
