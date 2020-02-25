from Singleton import ProjectService
from flask import jsonify

def get(user_id, project_id):
    return jsonify(ProjectService.getProject(user_id, int(project_id)))

def delete(user_id, project_id):
    resp = ProjectService.removeProject(user_id, int(project_id))

    if resp:
        return None, 204
    
    raise Exception("given project not removed")
