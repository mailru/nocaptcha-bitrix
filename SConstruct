import os, re
from copy import deepcopy
from datetime import datetime

vars = Variables()
vars.Add(EnumVariable('P', 'Part of the version to increment', 'p',
                      allowed_values=('M', 'm', 'p')))
env = Environment(variables=vars)
Help(vars.GenerateHelpText(env))

def parse_version(file):
    with open(file, 'r') as f:
        for line in f:
            match = re.search('[\\\'\\\"]VERSION[\\\'\\\"][^\d]+(\d+)\.(\d+)\.(\d+)', line)
            if not match:
                continue
            return [int(v) for v in deepcopy(match.group(1, 2, 3))]
    raise ValueError(u"Invalid version.php file format.")

def generate_version(file, vers):
    vers = u"{0}.{1}.{2}".format(*vers)
    date = datetime.today().strftime(u"%Y-%m-%d %H:%M:%S")
    data = u"""<?
$arModuleVersion = array(
    "VERSION" => "{0}",
    "VERSION_DATE" => "{1}",
);
?>
"""
    data = data.format(vers, date)
    with open(file, 'w') as f:
        f.writelines(data.encode('utf8'))

def increment_version(target, source, env):
    vers = parse_version('mailru.nocaptcha/install/version.php')
    inc = env.get('P', 'p')
    if inc == 'M':
        vers[0] += 1
        vers[1] = 0
        vers[2] = 0
    elif inc == 'm':
        vers[1] += 1
        vers[2] = 0
    else:
        vers[2] += 1
    generate_version('mailru.nocaptcha/install/version.php', vers)

inc_version = env.Command('vers', '', increment_version)

last_version = env.Command('.last_version', 'mailru.nocaptcha', [
    Copy('.last_version', 'mailru.nocaptcha'),
    'tar -czf .last_version.tar.gz .last_version',
    Delete('.last_version')])

env.AlwaysBuild(last_version)
env.Default(last_version)
