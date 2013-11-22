class Event < ActiveRecord::Base
include Paperclip::Glue
attr_accessible :start_at, :end_at, :name ,:content,:venue,:address,:furl,:turl,:latitude,:longitude,:avatar,:city,:event_type_id,
:event_language_id,:start_hour,:start_minute,:start_meridiem,:end_hour,:end_minute,:end_meridiem,:state,:country,:zipcode,:user_id,
:status
  has_attached_file :avatar, :styles => { :medium => "300x300>", :thumb => "267x175!>", :small => "146x97!>" }, :default_url => "default_upload_logo.gif",:path => ":rails_root/public/uploads/system/events/images/:id/:style/:filename",:url => "/uploads/system/events/images/:id/:style/:filename"
  

  has_event_calendar 
  belongs_to :event_type
  belongs_to :event_language
  belongs_to :user
  has_many :user_signed , :dependent => :destroy  
  geocoded_by :address


def self.event_count
where("end_at>='#{Date.today.strftime("2000-%m-%d")}'").count
end

   

end
