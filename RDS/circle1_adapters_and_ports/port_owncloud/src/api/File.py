from lib.ownCloudUser import OwncloudUser
from flask import request, send_file
import logging
from connexion_plus import FlaskOptimize


logger = logging.getLogger()


@FlaskOptimize.do_not_minify()
@FlaskOptimize.do_not_compress()
def index(filepath):
    userId = request.values.get("userId")
    logger.debug(f"userid {userId}")

    import os
    file = OwncloudUser(userId).getFile(filepath)

    rv = send_file(file, attachment_filename=os.path.basename(
        filepath), as_attachment=True, mimetype="application/octet-stream")
    logger.debug("disable passthrough")
    rv.direct_passthrough = False
    logger.debug("send response")
    return rv


@FlaskOptimize.do_not_minify()
@FlaskOptimize.do_not_compress()
def patch(filepath):
    raise NotImplementedError()


@FlaskOptimize.do_not_minify()
@FlaskOptimize.do_not_compress()
def post(filepath):
    raise NotImplementedError()


def delete(filepath):
    raise NotImplementedError()
